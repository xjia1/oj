#include "oj.h"
#include "rfc3174/sha1.h"

int sha1_file(const char *filename, char output[40])
{
    char *content;
    content = file_get_contents(filename);
    if (!content)
        return 1;
    int error = sha1_str(content, output);
    free(content);
    return error;
}

int sha1_str(const char *content, char output[40])
{
    SHA1Context sha;
    int error;
    uint8_t digest[20];
    int i;
    char hex[4];

    SHA1Reset(&sha);

    error = SHA1Input(&sha, (uint8_t *) content, strlen(content));
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

const char *sha1_to_string(char hash[40])
{
    static char buffer[41];
    memcpy(buffer, hash, 40);
    buffer[40] = 0;
    return buffer;
}
