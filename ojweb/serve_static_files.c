#include "oj.h"

void serve_static_files(const char *document_root)
{
    if (strstr(http_request_path(), ".."))
    {
        WARN("Potential attack: %s", http_request_path());
        return;
    }

    /* TODO send content type and content length in headers */
    char *filename = malloc(strlen(document_root) + strlen(http_request_path()) + 1);
    strcpy(filename, document_root);
    strcat(filename, http_request_path());

    char *data = file_get_contents(filename);
    free(filename);

    if (data)
    {
        print(data);
        free(data);
    }
}
