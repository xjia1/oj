#include <ctype.h>
#include <errno.h>
#include <inttypes.h>
#include <limits.h>
#include <math.h>
#include <stdarg.h>
#include <stddef.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

uint64_t current_time(void);

void logger_init(void);
void logger(const char *file, int line, const char *level, const char *format, ...);
#define DEBUG(...) logger(__FILE__, __LINE__, "[DEBUG]", __VA_ARGS__)
#define INFO(...) logger(__FILE__, __LINE__, "[INFO]", __VA_ARGS__)
#define WARN(...) logger(__FILE__, __LINE__, "[WARN]", __VA_ARGS__)
#define ERROR(...) logger(__FILE__, __LINE__, "[ERROR]", __VA_ARGS__)
#define FATAL(...) do { logger(__FILE__, __LINE__, "[FATAL]", __VA_ARGS__); exit(1); } while (0)

void rtrim(char *s);
int starts_with(const char *s, const char *t);
const char *strcut(const char *s, const char *t);

int file_exists(const char *filename);
int file_get_size(const char *filename);
char *file_get_contents(const char *filename);

int sha1_file(const char *filename, char output[40]);
int sha1_str(const char *str, char output[40]);

char *markdown(const char *filename);

void state_init(void);
void state_reset_problem(uint16_t problem_id);
void state_set_problem_title(uint16_t problem_id, const char *title);
void state_set_problem_max_code_size_bytes(uint16_t problem_id, uint32_t size);
void state_add_test_case(uint16_t problem_id, int score, int time_limit, int memory_limit, int input_size, const char input_hash[40], int answer_size, const char answer_hash[40]);
void state_set_problem_description(uint16_t problem_id, const char *html);
void state_set_problem_loaded(uint16_t problem_id);
int state_is_problem_loaded(uint16_t problem_id);
const char *state_problem_title(uint16_t problem_id);
const char *state_problem_description(uint16_t problem_id);
uint32_t state_problem_max_code_size_bytes(uint16_t problem_id);
uint64_t state_next_submit_id(void);
void state_add_submit(uint64_t id, const char *user, uint16_t problem_id, const char *code, const char *language, uint64_t submit_time, const char *submit_ip, char code_hash[40]);
void state_add_to_waiting_list(uint64_t id, uint64_t time);
uint64_t state_pop_waiting_list(uint64_t time);

void journal_init(void);
void journal_open(const char *filename, const char *mode);
void journal_reopen(const char *filename, const char *mode);
int journal_is_eof(void);
long journal_tell(void);
int journal_read(void *buffer, size_t len);
int journal_write(const void *data, size_t len);
int journal_read_uint8(uint8_t *u);
int journal_write_uint8(uint8_t u);
int journal_read_uint16(uint16_t *u);
int journal_write_uint16(uint16_t u);
int journal_expect_uint16(uint16_t u);
int journal_read_uint32(uint32_t *u);
int journal_write_uint32(uint32_t u);
int journal_read_uint64(uint64_t *u);
int journal_write_uint64(uint64_t u);
int journal_read_string(char **s);
int journal_write_string(const char *s);

int http_handle_request(void);
int http_request_method_is_GET(void);
int http_request_method_is_POST(void);
const char *http_request_ip(void);
const char *http_request_path(void);
const char *http_get_param(const char *name, const char *default_value);
const char *http_get_header(const char *name, const char *default_value);
const char *http_get_cookie(const char *name, const char *default_value);

void http_server_init(void);
void http_server_set_port(int port);
void http_server_set_backlog(int backlog);
void http_server_set_timeout(long sec, long usec);
void http_server_start(void);
int http_server_accept_a_request(void);
int http_server_recv(void *buf, size_t len);
int http_server_send(const void *buf, size_t len);
void http_server_close_connection(void);

int conf_load(const char *filename);
const char *conf_get(const char *name, const char *default_value);

void output_buffer_init(void);
void output_buffer_reset(void);
void print(const char *s);
void output_buffer_flush(void);

void print_header(const char *title);
void print_footer(void);
void print_navbar(void);
void print_current_year(void);
void print_int(int i);
void print_pagination(int curpage, int total, const char *prefix);
void print_radio(const char *name, const char *value, const char *id, const char *label);

const char *current_user(void);

int priv_load_problem(void);
int priv_read_problem(int problem_id);
int priv_submit(int problem_id);
int priv_fetch_task(void);

int language_is_supported(const char *lang);
int language_is_allowed(const char *lang, uint16_t problem_id);

int pg_index(void);
int pg_submit(void);

int q_list_problems(void);
int q_read_problem(void);

int cmd_submit(void);
int cmd_submit_replay(void);

int cmd_load_problem(void);
int cmd_load_problem_replay(void);

int cmd_fetch_task(void);
int cmd_fetch_task_replay(void);

void serve_static_files(const char *document_root);
