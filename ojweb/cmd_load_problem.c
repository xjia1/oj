#include "oj.h"

#define MAX_FILENAME_SIZE 512
#define MAX_PROBLEMS_NUM 10000

#define COMMAND_ID 0x0101
static uint16_t Problem_ID;

static int execute()
{
    char filename[MAX_FILENAME_SIZE];

    if (Problem_ID < 0 || Problem_ID >= MAX_PROBLEMS_NUM)
    {
        print("id out of range");
        return 1;
    }

    if (priv_load_problem() != 0)
    {
        print("authorization failed");
        return 1;
    }

    state_reset_problem(Problem_ID);

    sprintf(filename, "/var/lib/sjtuoj/problems/%" PRIu16 "/problem.conf", Problem_ID);
    if (conf_load(filename) != 0)
    {
        ERROR("Unable to load problem configuration %s", filename);
        return 1;
    }
    state_set_problem_title(Problem_ID, conf_get("title", ""));
    state_set_problem_max_code_size_bytes(Problem_ID, atoi(conf_get("max_code_size_bytes", "4096")));
    /* TODO parse languages and headers whitelists */

    int num_test_cases = atoi(conf_get("case_count", "10"));
    const char *default_score = conf_get("case_score", "10");
    const char *default_time_limit = conf_get("time_limit", "1000");
    const char *default_memory_limit = conf_get("memory_limit", "65536");

    for (int i = 1; i <= num_test_cases; i++)
    {
        int score, time_limit, memory_limit;
        int input_size, answer_size;
        char input_hash[40], answer_hash[40];

        sprintf(filename, "/var/lib/sjtuoj/problems/%" PRIu16 "/data/%d.conf", Problem_ID, i);
        if (conf_load(filename) == 0)
        {
            score = atoi(conf_get("case_score", default_score));
            time_limit = atoi(conf_get("time_limit", default_time_limit));
            memory_limit = atoi(conf_get("memory_limit", default_memory_limit));
        }
        else
        {
            score = atoi(default_score);
            time_limit = atoi(default_time_limit);
            memory_limit = atoi(default_memory_limit);
        }

        sprintf(filename, "/var/lib/sjtuoj/problems/%" PRIu16 "/data/%d.in", Problem_ID, i);
        if (!file_exists(filename))
        {
            ERROR("Unable to find input file %s", filename);
            return 1;
        }
        input_size = file_get_size(filename);
        if (sha1(filename, input_hash) != 0)
        {
            ERROR("Unable to calculate SHA-1 of %s", filename);
            return 1;
        }

        sprintf(filename, "/var/lib/sjtuoj/problems/%" PRIu16 "/data/%d.out", Problem_ID, i);
        if (!file_exists(filename))
        {
            ERROR("Unable to find output file %s", filename);
            return 1;
        }
        answer_size = file_get_size(filename);
        if (sha1(filename, answer_hash) != 0)
        {
            ERROR("Unable to calculate SHA-1 of %s", filename);
            return 1;
        }

        state_add_test_case(Problem_ID, score, time_limit, memory_limit, input_size, input_hash, answer_size, answer_hash);
    }

    sprintf(filename, "/var/lib/sjtuoj/problems/%" PRIu16 "/problem.text", Problem_ID);
    char *html = markdown(filename);
    if (!html)
    {
        ERROR("Unable to load problem text %s", filename);
        return 1;
    }
    state_set_problem_description(Problem_ID, html);
    free(html);

    state_set_problem_loaded(Problem_ID);

    INFO("Problem %" PRIu16 " loaded", Problem_ID);
    print("ok");
    return 0;
}

static void persist()
{
    if (journal_write_uint16(COMMAND_ID) != 0)
        FATAL("Unable to write command ID");
    if (journal_write_uint16(Problem_ID) != 0)
        FATAL("Unable to write problem ID");
}

int cmd_load_problem()
{
    if (http_request_method_is_POST() && strcmp(http_request_path(), "/admin/loadproblem") == 0)
    {
        Problem_ID = atoi(http_get_param("id", "-1"));
        if (execute() == 0)
            persist();
        return 0;
    }
    return 1;
}

int cmd_load_problem_replay()
{
    if (journal_expect_uint16(COMMAND_ID) == 0)
    {
        if (journal_read_uint16(&Problem_ID) != 0)
            FATAL("Unable to load problem ID");
        if (execute() != 0)
            FATAL("Unable to replay load problem");
        return 0;
    }
    return 1;
}
