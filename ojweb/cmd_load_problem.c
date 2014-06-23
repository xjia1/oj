#include "oj.h"

#define COMMAND_ID 0x0101
static uint16_t Problem_ID;

int cmd_load_problem()
{
    /* TODO cmd_load_problem */
    return 1;
}

int cmd_load_problem_replay()
{
    int error;

    error = journal_expect_uint16(COMMAND_ID);
    if (error)
        return error;

    error = journal_read_uint16(&Problem_ID);
    if (error)
        return error;

    return 0;
}
