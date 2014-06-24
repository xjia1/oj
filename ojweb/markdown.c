#include "oj.h"

#define MAX_FILENAME_SIZE 512
#define TMP_PATH ""
#define PANDOC_PATH "/usr/local/bin/pandoc"

char *markdown(const char *filename)
{
    char tmp[L_tmpnam];
    char tmpfn[MAX_FILENAME_SIZE];
    char cmd[MAX_FILENAME_SIZE * 4];

    tmpnam(tmp);
    sprintf(tmpfn, "%s%s", TMP_PATH, tmp);

    sprintf(cmd, "%s -f markdown_github -t html5 -o \"%s\" \"%s\"", PANDOC_PATH, tmpfn, filename);
    if (system(NULL))
        system(cmd);
    else
        FATAL("Unable to find command interpreter");

    char *html = file_get_contents(tmpfn);
    remove(tmpfn);
    return html;
}
