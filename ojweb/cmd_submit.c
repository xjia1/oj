#include "oj.h"

#define MAX_PROBLEMS_NUM 10000

#define COMMAND_ID 0x0102
static uint64_t Submit_ID;
static char *Submit_User;
static uint16_t Problem_ID;
static char *Code;
static char *Language;
static uint64_t Submit_Time;
static char *Submit_IP;
static char Code_SHA1[40];

static void free_memory()
{
    free(Submit_User);
    free(Code);
    free(Language);
    free(Submit_IP);
}

static int execute()
{
    state_add_submit(Submit_ID, Submit_User, Problem_ID, Code, Language, Submit_Time, Submit_IP, Code_SHA1);
    state_add_to_waiting_list(Submit_ID, Submit_Time);
    INFO("Submit ID is %" PRIu64 " and code SHA-1 is %s", Submit_ID, sha1_to_string(Code_SHA1));
    print("ok");
    return 0;
}

static void persist()
{
    if (journal_write_uint16(COMMAND_ID) != 0)
        FATAL("Unable to write command ID");

    if (journal_write_uint64(Submit_ID) != 0)
        FATAL("Unable to write submit ID");

    if (journal_write_string(Submit_User) != 0)
        FATAL("Unable to write submit user");

    if (journal_write_uint16(Problem_ID) != 0)
        FATAL("Unable to write problem ID");

    if (journal_write_string(Code) != 0)
        FATAL("Unable to write code");

    if (journal_write_string(Language) != 0)
        FATAL("Unable to write language");

    if (journal_write_uint64(Submit_Time) != 0)
        FATAL("Unable to write submit time");

    if (journal_write_string(Submit_IP) != 0)
        FATAL("Unable to write submit IP");

    if (journal_write(Code_SHA1, 40) != 0)
        FATAL("Unable to write code SHA-1");

    free_memory();
}

int cmd_submit()
{
    if (!http_request_method_is_POST())
        return 1;

    if (strcmp(http_request_path(), "/submit") != 0)
        return 1;

    Problem_ID = atoi(http_get_param("problem_id", "-1"));

    if (Problem_ID < 0 || Problem_ID >= MAX_PROBLEMS_NUM)
    {
        DEBUG("id out of range: %" PRIu16, Problem_ID);
        print("id out of range");
        return 0;
    }

    if (!state_is_problem_loaded(Problem_ID))
    {
        print("problem not loaded");
        return 0;
    }

    if (priv_submit(Problem_ID) != 0)
    {
        print("authorization failed");
        return 0;
    }

    Code = strdup(http_get_param("code", ""));
    Language = strdup(http_get_param("language", ""));
    Submit_User = strdup(current_user());
    Submit_IP = strdup(http_request_ip());

    if (strlen(Code) <= 0)
    {
        print("code empty");
        free_memory();
        return 0;
    }

    if (strlen(Code) > state_problem_max_code_size_bytes(Problem_ID))
    {
        print("code too long");
        free_memory();
        return 0;
    }

    if (strlen(Language) <= 0)
    {
        print("language empty");
        free_memory();
        return 0;
    }

    if (!language_is_supported(Language))
    {
        print("language not supported");
        free_memory();
        return 0;
    }

    if (!language_is_allowed(Language, Problem_ID))
    {
        print("language not allowed");
        free_memory();
        return 0;
    }

    Submit_ID = state_next_submit_id();
    Submit_Time = current_time();
    sha1_str(Code, Code_SHA1);

    if (execute() == 0)
        persist();

    return 0;
}

int cmd_submit_replay()
{
    if (journal_expect_uint16(COMMAND_ID) != 0)
        return 1;

    if (journal_read_uint64(&Submit_ID) != 0)
        FATAL("Unable to read submit ID");

    if (journal_read_string(&Submit_User) != 0)
        FATAL("Unable to read submit user");

    if (journal_read_uint16(&Problem_ID) != 0)
        FATAL("Unable to read problem ID");

    if (journal_read_string(&Code) != 0)
        FATAL("Unable to read code");

    if (journal_read_string(&Language) != 0)
        FATAL("Unable to read language");

    if (journal_read_uint64(&Submit_Time) != 0)
        FATAL("Unable to read submit time");

    if (journal_read_string(&Submit_IP) != 0)
        FATAL("Unable to read submit IP");

    if (journal_read(Code_SHA1, 40) != 0)
        FATAL("Unable to read code SHA-1");

    if (execute() != 0)
        FATAL("Unable to replay submit");

    free_memory();
    return 0;
}
