#include "oj.h"

#define MAX_PROBLEMS_NUM 10000
#define MAX_TITLE_SIZE 256
#define MAX_ALLOWED_LANGUAGES_NUM 8
#define MAX_ALLOWED_LANGUAGE_SIZE 32
#define MAX_ALLOWED_HEADERS_NUM 8
#define MAX_ALLOWED_HEADER_SIZE 32
#define MAX_TEST_CASES_NUM 100

struct Test_Case
{
    int8_t Score;
    uint16_t Time_Limit_Millisec;
    uint32_t Memory_Limit_KB;
    uint32_t Input_Size_Bytes;
    uint32_t Answer_Size_Bytes;
    char Input_SHA1[40];
    char Answer_SHA1[40];
};

struct Problem
{
    uint16_t ID;
    uint8_t Is_Visible;
    char *Title;
    char *Description;
    /*
    uint8_t Num_Allowed_Languages;
    uint8_t Num_Allowed_Headers;
    char Allowed_Languages[MAX_ALLOWED_LANGUAGES_NUM][MAX_ALLOWED_LANGUAGE_SIZE];
    char Allowed_Headers[MAX_ALLOWED_HEADERS_NUM][MAX_ALLOWED_HEADER_SIZE];
    */
    uint32_t Max_Code_Size_Bytes;
    uint8_t Num_Test_Cases;
    struct Test_Case Test_Cases[MAX_TEST_CASES_NUM];
    uint8_t Is_Loaded;
};

struct System_State
{
    struct Problem Problems[MAX_PROBLEMS_NUM];
};

struct System_State State;

void state_init()
{
    memset(&State, 0, sizeof(State));
}

void state_reset_problem(uint16_t problem_id)
{
    /* TODO free previously allocated memory */
    memset(&State.Problems[problem_id], 0, sizeof(struct Problem));
    State.Problems[problem_id].ID = problem_id;
}

void state_set_problem_title(uint16_t problem_id, const char *title)
{
    State.Problems[problem_id].Title = strdup(title);
}

void state_set_problem_max_code_size_bytes(uint16_t problem_id, int size)
{
    State.Problems[problem_id].Max_Code_Size_Bytes = size;
}

void state_add_test_case(uint16_t problem_id, int score, int time_limit, int memory_limit, int input_size, const char input_hash[40], int answer_size, const char answer_hash[40])
{
    struct Test_Case *tc;
    tc = &State.Problems[problem_id].Test_Cases[State.Problems[problem_id].Num_Test_Cases];
    tc->Score = score;
    tc->Time_Limit_Millisec = time_limit;
    tc->Memory_Limit_KB = memory_limit;
    tc->Input_Size_Bytes = input_size;
    tc->Answer_Size_Bytes = answer_size;
    memcpy(tc->Input_SHA1, input_hash, 40);
    memcpy(tc->Answer_SHA1, answer_hash, 40);
    State.Problems[problem_id].Num_Test_Cases++;
}

void state_set_problem_description(uint16_t problem_id, const char *html)
{
    State.Problems[problem_id].Description = strdup(html);
}

void state_set_problem_loaded(uint16_t problem_id)
{
    State.Problems[problem_id].Is_Loaded = 1;
}

int state_is_problem_loaded(uint16_t problem_id)
{
    return State.Problems[problem_id].Is_Loaded;
}

const char *state_problem_title(uint16_t problem_id)
{
    return State.Problems[problem_id].Title;
}

const char *state_problem_description(uint16_t problem_id)
{
    return State.Problems[problem_id].Description;
}
