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

void logger_init(void);
void logger(const char *file, int line, const char *level, const char *format, ...);
#define DEBUG(...) logger(__FILE__, __LINE__, "[DEBUG]", __VA_ARGS__)
#define INFO(...) logger(__FILE__, __LINE__, "[INFO]", __VA_ARGS__)
#define WARN(...) logger(__FILE__, __LINE__, "[WARN]", __VA_ARGS__)
#define ERROR(...) logger(__FILE__, __LINE__, "[ERROR]", __VA_ARGS__)
#define FATAL(...) do { logger(__FILE__, __LINE__, "[FATAL]", __VA_ARGS__); exit(1); } while (0)

void rtrim(char *s);

void journal_init(void);
void journal_open(const char *filename, const char *mode);
void journal_reopen(const char *filename, const char *mode);
int journal_is_eof(void);
long journal_tell(void);
int journal_read_uint8(uint8_t *u);
int journal_read_uint16(uint16_t *u);
int journal_expect_uint16(uint16_t u);

int http_handle_request(void);
int http_request_method_is_GET(void);
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

int file_exists(const char *filename);
int file_get_size(const char *filename);
char *file_get_contents(const char *filename);

void conf_load(const char *filename);
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

int pg_index(void);
int pg_submit(void);

int q_list_problems(void);
int q_read_problem(void);

int cmd_submit(void);
int cmd_submit_replay(void);

int cmd_load_problem(void);
int cmd_load_problem_replay(void);

void serve_static_files(const char *document_root);
