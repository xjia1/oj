#include "oj.h"
#include "rfc3174/sha1.h"

int sha1(const char *filename, char output[40])
{
    char *content;
    SHA1Context sha;
    int error;
    uint8_t digest[20];
    int i;
    char hex[4];

    content = file_get_contents(filename);
    if (!content)
        return 1;

    SHA1Reset(&sha);

    error = SHA1Input(&sha, (uint8_t *) content, file_get_size(filename));
    free(content);
    if (error)
        return 1;

    error = SHA1Result(&sha, digest);
    if (error)
        return 1;

    for (i = 0; i < 20; i++)
    {
        sprintf(hex, "%02x", digest[i]);
        output[i * 2 + 0] = hex[0];
        output[i * 2 + 1] = hex[1];
    }

    return 0;
}
