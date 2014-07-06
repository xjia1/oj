#include "oj.h"
#include "queue.h"

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

struct Submit
{
    uint64_t ID;
    char *Submit_User;
    uint16_t Problem_ID;
    char *Code;
    char *Language;
    uint64_t Submit_Time;
    char *Submit_IP;
    char Code_SHA1[40];
};

struct Judge_Queue_Node
{
    uint64_t Submit_ID;
    uint64_t Enqueue_Time;
};

struct System_State
{
    struct Problem Problems[MAX_PROBLEMS_NUM];

    size_t Num_Submits;
    struct Submit Submits[1000000];

    queue_t Waiting_List;
    queue_t Running_List;
};

static struct System_State State;

void state_init()
{
    memset(&State, 0, sizeof(State));
    State.Waiting_List = queue_create();
    State.Running_List = queue_create();
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

void state_set_problem_max_code_size_bytes(uint16_t problem_id, uint32_t size)
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

uint32_t state_problem_max_code_size_bytes(uint16_t problem_id)
{
    return State.Problems[problem_id].Max_Code_Size_Bytes;
}

uint64_t state_next_submit_id()
{
    uint64_t result = 0;
    for (size_t i = 0; i < State.Num_Submits; i++)
        if (State.Submits[i].ID > result)
            result = State.Submits[i].ID;
    return result + 1;
}

void state_add_submit(uint64_t id, const char *user, uint16_t problem_id, const char *code, const char *language, uint64_t submit_time, const char *submit_ip, char code_hash[40])
{
    const size_t i = State.Num_Submits;
    State.Submits[i].ID = id;
    State.Submits[i].Submit_User = strdup(user);
    State.Submits[i].Problem_ID = problem_id;
    State.Submits[i].Code = strdup(code);
    State.Submits[i].Language = strdup(language);
    State.Submits[i].Submit_Time = submit_time;
    State.Submits[i].Submit_IP = strdup(submit_ip);
    memcpy(State.Submits[i].Code_SHA1, code_hash, 40);
    State.Num_Submits++;
}

void state_add_to_waiting_list(uint64_t id, uint64_t time)
{
    struct Judge_Queue_Node *node = malloc(sizeof(struct Judge_Queue_Node));
    node->Submit_ID = id;
    node->Enqueue_Time = time;
    queue_push(State.Waiting_List, node);
}

uint64_t state_pop_waiting_list(uint64_t time)
{
    struct Judge_Queue_Node *node = queue_pop(State.Waiting_List);
    node->Enqueue_Time = time;
    queue_push(State.Running_List, node);
    return node->Submit_ID;
}

size_t state_num_submits()
{
    return State.Num_Submits;
}

const char *state_submit_code_at(size_t index)
{
    return State.Submits[index].Code;
}

const char *state_submit_code_sha1_at(size_t index)
{
    return State.Submits[index].Code_SHA1;
}
