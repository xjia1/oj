#include "oj.h"

#ifdef _WIN32
    #define WIN32_LEAN_AND_MEAN
    #include <windows.h>
    #include <winsock.h>
    #pragma comment(lib, "ws2_32.lib")
#else
    #include <unistd.h>
    #include <arpa/inet.h>
    #include <netinet/in.h>
    #include <sys/socket.h>
    #include <sys/time.h>
    #include <sys/types.h>
    static int closesocket(int fd) { return close(fd); }
#endif

static int Port;
static int Backlog;
static struct timeval Timeout;
static int Server_Socket;
static int Client_Socket;

void http_server_init()
{
#ifdef _WIN32
    WSADATA wsaData;
    if (WSAStartup(MAKEWORD(2, 2), &wsaData) != 0)
        FATAL("WSAStartup 2.2 failed: %s", strerror(errno));
#endif

    if ((Server_Socket = socket(AF_INET, SOCK_STREAM, 0)) == -1)
        FATAL("socket: %s", strerror(errno));

    int reuseaddr = 1;
    if (setsockopt(Server_Socket, SOL_SOCKET, SO_REUSEADDR, (void *) &reuseaddr, sizeof(int)) == -1)
        FATAL("setsockopt SO_REUSEADDR: %s", strerror(errno));
}

void http_server_set_port(int port)
{
    Port = port;
}

void http_server_set_backlog(int backlog)
{
    Backlog = backlog;
}

void http_server_set_timeout(long sec, long usec)
{
    Timeout.tv_sec = sec;
    Timeout.tv_usec = usec;
}

void http_server_start()
{
    struct sockaddr_in serv;

    memset(&serv, 0, sizeof(serv));
    serv.sin_family = AF_INET;
    serv.sin_addr.s_addr = htonl(INADDR_ANY);
    serv.sin_port = htons(Port);

    if (bind(Server_Socket, (struct sockaddr *) &serv, sizeof(serv)) == -1)
        FATAL("bind: %s", strerror(errno));

    if (listen(Server_Socket, Backlog) == -1)
        FATAL("listen: %s", strerror(errno));
}

int http_server_accept_a_request()
{
    struct sockaddr_in dest;
    unsigned socksize = sizeof(struct sockaddr_in);

    if ((Client_Socket = accept(Server_Socket, (struct sockaddr *) &dest, &socksize)) == -1)
    {
        ERROR("accept: %s", strerror(errno));
        return 1;
    }

    if (setsockopt(Client_Socket, SOL_SOCKET, SO_RCVTIMEO, (void *) &Timeout, sizeof(Timeout)) == -1)
    {
        ERROR("setsockopt SO_RCVTIMEO: %s", strerror(errno));
        return 1;
    }

    return http_handle_request();
}

int http_server_recv(void *buf, size_t len)
{
    return recv(Client_Socket, buf, len, 0);
}

int http_server_send(const void *buf, size_t len)
{
    return send(Client_Socket, buf, len, 0);
}

void http_server_close_connection()
{
    closesocket(Client_Socket);
}
