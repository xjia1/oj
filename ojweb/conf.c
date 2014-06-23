#include "oj.h"

#define MAX_CONF_ITEMS_NUM 32
#define MAX_CONF_ITEM_NAME_SIZE 32
#define MAX_CONF_ITEM_VALUE_SIZE 256

struct Conf_Item
{
    char Name[MAX_CONF_ITEM_NAME_SIZE];
    char Value[MAX_CONF_ITEM_VALUE_SIZE];
};

struct Conf
{
    int Num_Items;
    struct Conf_Item Items[MAX_CONF_ITEMS_NUM];
};

static struct Conf Conf;

void conf_load(const char *filename)
{
    FILE *f;
    if (!(f = fopen(filename, "r")))
        FATAL("Unable to open conf %s", filename);

    const int c1 = fgetc(f);
    const int c2 = fgetc(f);
    const int c3 = fgetc(f);
    if (!(c1 == 0xEF && c2 == 0xBB && c3 == 0xBF))
    {
        /* not using BOM, put them back */
        ungetc(c3, f);
        ungetc(c2, f);
        ungetc(c1, f);
    }

    memset(&Conf, 0, sizeof(struct Conf));

    char line[MAX_CONF_ITEM_NAME_SIZE + MAX_CONF_ITEM_VALUE_SIZE + 2];
    while (memset(line, 0, sizeof(line)), fgets(line, sizeof(line) - 1, f))
    {
        char *p = strchr(line, ':');
        if (!p) continue;
        while (*p == ':' || *p == ' ') *p++ = 0;
        strcpy(Conf.Items[Conf.Num_Items].Name, line);
        strcpy(Conf.Items[Conf.Num_Items].Value, p);
        rtrim(Conf.Items[Conf.Num_Items].Name);
        rtrim(Conf.Items[Conf.Num_Items].Value);
        Conf.Num_Items++;
        if (Conf.Num_Items >= MAX_CONF_ITEMS_NUM) break;
    }

    fclose(f);
}

const char *conf_get(const char *name, const char *default_value)
{
    for (int i = 0; i < Conf.Num_Items; i++)
        if (strcmp(Conf.Items[i].Name, name) == 0)
            return Conf.Items[i].Value;
    return default_value;
}
