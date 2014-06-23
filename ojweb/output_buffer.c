#include "oj.h"

static char *Buffer;
static size_t Capacity;
static size_t Length;

void output_buffer_init()
{
    Capacity = 4096;
    Buffer = malloc(Capacity);
}

void output_buffer_reset()
{
    Length = 0;
}

static void resize(size_t need)
{
    size_t new_capacity = Capacity * 2;
    while (new_capacity < need)
        new_capacity *= 2;
    char *new_buffer = malloc(new_capacity);
    memcpy(new_buffer, Buffer, Length);
    free(Buffer);
    Buffer = new_buffer;
    Capacity = new_capacity;
}

void print(const char *s)
{
    size_t len = strlen(s);
    if (Length + len > Capacity)
        resize(Length + len);
    memcpy(Buffer + Length, s, len);
    Length += len;
}

void output_buffer_flush()
{
    /* TODO create headers on the fly */
    static const char *msg = "HTTP/1.1 200 OK\r\nServer: SJTU OJ\r\nConnection: close\r\n\r\n";
    http_server_send(msg, strlen(msg));
    http_server_send(Buffer, Length);
}
