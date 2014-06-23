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
    char *Input;
    char *Answer;
};

struct Problem
{
    uint16_t ID;
    uint8_t Is_Visible;
    char Title[MAX_TITLE_SIZE];
    char *Description_HTML;
    uint8_t Num_Allowed_Languages;
    uint8_t Num_Allowed_Headers;
    char Allowed_Languages[MAX_ALLOWED_LANGUAGES_NUM][MAX_ALLOWED_LANGUAGE_SIZE];
    char Allowed_Headers[MAX_ALLOWED_HEADERS_NUM][MAX_ALLOWED_HEADER_SIZE];
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
