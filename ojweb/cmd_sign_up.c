#include "oj.h"

#define COMMAND_ID 0x0104
static char *Username;
static char *Password;
static char *Name;
static char *Sex;
static char *Email;
static char *Phone;
static char *Memo;

static void free_memory()
{
    free(Username);
    free(Password);
    free(Name);
    free(Sex);
    free(Email);
    free(Phone);
    free(Memo);
}

static int execute()
{
    /* TODO execute cmd_sign_up */
    INFO("Sign up username is %s and password is %s", Username, Password);
    print("ok");
    return 0;
}

static void persist()
{
    if (journal_write_uint16(COMMAND_ID) != 0)
        FATAL("Unable to write command ID");

    if (journal_write_string(Username) != 0)
        FATAL("Unable to write username");

    if (journal_write_string(Password) != 0)
        FATAL("Unable to write password");

    if (journal_write_string(Name) != 0)
        FATAL("Unable to write name");

    if (journal_write_string(Sex) != 0)
        FATAL("Unable to write sex");

    if (journal_write_string(Email) != 0)
        FATAL("Unable to write email");

    if (journal_write_string(Phone) != 0)
        FATAL("Unable to write phone");

    if (journal_write_string(Memo) != 0)
        FATAL("Unable to write memo");

    free_memory();
}

int cmd_sign_up()
{
    if (!http_request_method_is_POST())
        return 1;

    if (strcmp(http_request_path(), "/signup") != 0)
        return 1;

    Username = strdup(http_get_param("username", ""));
    Password = strdup(http_get_param("password", ""));
    Name = strdup(http_get_param("name", ""));
    Sex = strdup(http_get_param("sex", ""));
    Email = strdup(http_get_param("email", ""));
    Phone = strdup(http_get_param("phone", ""));
    Memo = strdup(http_get_param("memo", ""));

    if (execute() == 0)
        persist();

    return 0;
}

int cmd_sign_up_replay()
{
    if (journal_expect_uint16(COMMAND_ID) != 0)
        return 1;

    if (journal_read_string(&Username) != 0)
        FATAL("Unable to read username");

    if (journal_read_string(&Password) != 0)
        FATAL("Unable to read password");

    if (journal_read_string(&Name) != 0)
        FATAL("Unable to read name");

    if (journal_read_string(&Sex) != 0)
        FATAL("Unable to read sex");

    if (journal_read_string(&Email) != 0)
        FATAL("Unable to read email");

    if (journal_read_string(&Phone) != 0)
        FATAL("Unable to read phone");

    if (journal_read_string(&Memo) != 0)
        FATAL("Unable to read memo");

    if (execute() != 0)
        FATAL("Unable to replay sign up");

    free_memory();
    return 0;
}
