#include "oj.h"

#define PARSE_HTTP_METHOD(X) do {                             \
    if (memcmp(Request_Line, #X " ", strlen(#X " ")) == 0) {  \
        memset(Request_Line, 0, strlen(#X " "));              \
        Request.Method = X;                                   \
        Request.Path = Request_Line + strlen(#X " ");         \
        return 0;                                             \
    }                                                         \
} while (0)

struct String_Pair
{
    const char *Name;
    const char *Value;
};

enum Http_Method { GET, HEAD, POST, PUT, DELETE, TRACE, OPTIONS, CONNECT, PATCH };

#define MAX_PARAMS_NUM  32
#define MAX_HEADERS_NUM 32
#define MAX_COOKIES_NUM 32

struct Http_Request
{
    enum Http_Method Method;
    const char *Path;
    uint8_t Num_Params;
    uint8_t Num_Headers;
    uint8_t Num_Cookies;
    struct String_Pair Params[MAX_PARAMS_NUM];
    struct String_Pair Headers[MAX_HEADERS_NUM];
    struct String_Pair Cookies[MAX_COOKIES_NUM];
    uint32_t Content_Length;
    const char *Session_ID;
};

static struct Http_Request Request;

#define BUFSIZE 4096
static char Headers_Buffer[BUFSIZE * 2];  /* reserve space to simplify code */
static char Body_Buffer[BUFSIZE * 10];  /* max body size is 40KB */

static const char *rnrn;
static char *Request_Line;
static char *Current_Header_Line;

static char *find_next_line(char *current_line)
{
    char *r = memchr(current_line, '\r', BUFSIZE);
    if (r == current_line) return NULL;
    return r + 2;
}

static int read_headers()
{
    memset(Headers_Buffer, 0, sizeof(Headers_Buffer));

    char *buf = Headers_Buffer;
    while (buf < Headers_Buffer + BUFSIZE)
    {
        int num_bytes = http_server_recv(buf, BUFSIZE);
        if (num_bytes <= 0) break;
        buf += num_bytes;

        rnrn = strstr(Headers_Buffer, "\r\n\r\n");
        if (rnrn) break;
    }

    if (buf == Headers_Buffer)
    {
        ERROR("empty request");
        return 1;
    }

    Request_Line = Headers_Buffer;
    Current_Header_Line = find_next_line(Request_Line);

    return 0;
}

static int parse_method()
{
    PARSE_HTTP_METHOD(GET);
    PARSE_HTTP_METHOD(HEAD);
    PARSE_HTTP_METHOD(POST);
    PARSE_HTTP_METHOD(PUT);
    PARSE_HTTP_METHOD(DELETE);
    PARSE_HTTP_METHOD(TRACE);
    PARSE_HTTP_METHOD(OPTIONS);
    PARSE_HTTP_METHOD(CONNECT);
    PARSE_HTTP_METHOD(PATCH);

    ERROR("unknown http method");
    return 1;
}

static int parse_path()
{
    for (char *p = Request_Line; p < Current_Header_Line; p++)
        if (*p == '?' || *p == '=' || *p == '&' || *p == ' ' || *p == '\r' || *p == '\n')
            *p = 0;

    for (const char *p = Request.Path + strlen(Request.Path) + 1; p < Current_Header_Line;)
    {
        Request.Params[Request.Num_Params].Name = p;
        p += strlen(p) + 1;

        if (p < Current_Header_Line)
        {
            Request.Params[Request.Num_Params++].Value = p;
            p += strlen(p) + 1;
        }
    }

    return 0;
}

static int parse_headers()
{
    while (Current_Header_Line)
    {
        char *r = memchr(Current_Header_Line, '\r', BUFSIZE);
        if (r == Current_Header_Line) break;
        r[0] = r[1] = 0;

        char *colon = memchr(Current_Header_Line, ':', BUFSIZE);
        if (colon > r)
        {
            ERROR("parse_headers");
            return 1;
        }
        colon[0] = colon[1] = 0;

        Request.Headers[Request.Num_Headers].Name = Current_Header_Line;
        Request.Headers[Request.Num_Headers].Value = colon + 2;
        Request.Num_Headers++;

        Current_Header_Line = r + 2;
    }

    Request.Content_Length = atoi(http_get_header("Content-Length", "0"));

    return 0;
}

static int parse_cookies()
{
    static char cookie[BUFSIZE];

    strcpy(cookie, http_get_header("Cookie", ""));
    const int n = strlen(cookie);

    for (char *p = cookie; p < cookie + n; p++)
        if (*p == '=' || *p == ';' || *p == ' ')
            *p = 0;

    for (const char *p = cookie; p < cookie + n;)
    {
        Request.Cookies[Request.Num_Cookies].Name = p;
        p += strlen(p) + 1;

        if (p < cookie + n)
        {
            Request.Cookies[Request.Num_Cookies++].Value = p;
            p += strlen(p) + 1;
        }
    }

    Request.Session_ID = http_get_cookie("SESSION_ID", "");

    return 0;
}

static int read_body()
{
    if (Request.Content_Length >= sizeof(Body_Buffer))
    {
        ERROR("http_read_body: content length too large");
        return 1;
    }

    memset(Body_Buffer, 0, sizeof(Body_Buffer));
    strcpy(Body_Buffer, rnrn + 4);

    const int n = strlen(Body_Buffer);
    char *buf = Body_Buffer + n;
    int remaining = Request.Content_Length - n;
    while (remaining > 0)
    {
        int num_bytes = http_server_recv(buf, remaining);
        if (num_bytes <= 0) break;
        buf += num_bytes;
        remaining -= num_bytes;
    }
    if (remaining > 0)
    {
        ERROR("http_read_body: incomplete body");
        return 1;
    }

    return 0;
}

static int parse_body()
{
    for (char *p = Body_Buffer; p < Body_Buffer + Request.Content_Length; p++)
        if (*p == '=' || *p == '&')
            *p = 0;

    for (const char *p = Body_Buffer; p < Body_Buffer + Request.Content_Length;)
    {
        Request.Params[Request.Num_Params].Name = p;
        p += strlen(p) + 1;

        if (p < Body_Buffer + Request.Content_Length)
        {
            Request.Params[Request.Num_Params++].Value = p;
            p += strlen(p) + 1;
        }
    }

    return 0;
}

int http_handle_request()
{
    int error;

    memset(&Request, 0, sizeof(Request));
    memset(&Headers_Buffer, 0, sizeof(Headers_Buffer));
    memset(&Body_Buffer, 0, sizeof(Body_Buffer));

    error = read_headers();
    if (error)
        return error;

    error = parse_method();
    if (error)
        return error;

    error = parse_path();
    if (error)
        return error;

    error = parse_headers();
    if (error)
        return error;

    error = parse_cookies();
    if (error)
        return error;

    DEBUG("%s %s", (Request.Method == GET ? "GET" : "POST"), Request.Path);

    error = read_body();
    if (error)
        return error;

    error = parse_body();
    if (error)
        return error;

    return 0;
}

int http_request_method_is_GET()
{
    return Request.Method == GET;
}

int http_request_method_is_POST()
{
    return Request.Method == POST;
}

const char *http_request_path()
{
    return Request.Path;
}

const char *http_get_param(const char *name, const char *default_value)
{
    for (int i = 0; i < Request.Num_Params; i++)
        if (strcmp(Request.Params[i].Name, name) == 0)
            return Request.Params[i].Value;
    return default_value;
}

const char *http_get_header(const char *name, const char *default_value)
{
    for (int i = 0; i < Request.Num_Headers; i++)
        if (strcmp(Request.Headers[i].Name, name) == 0)
            return Request.Headers[i].Value;
    return default_value;
}

const char *http_get_cookie(const char *name, const char *default_value)
{
    for (int i = 0; i < Request.Num_Cookies; i++)
        if (strcmp(Request.Cookies[i].Name, name) == 0)
            return Request.Cookies[i].Value;
    return default_value;
}
