#include "oj.h"
#include "sundown/markdown.h"
#include "sundown/html.h"
#include "sundown/buffer.h"

#define READ_UNIT 1024
#define OUTPUT_UNIT 64

char *markdown(const char *filename)
{
    int ret;
    FILE *in;
    char *result;

    struct buf *ib, *ob;
    struct sd_callbacks callbacks;
    struct html_renderopt options;
    struct sd_markdown *markdown;

    in = fopen(filename, "r");
    if (!in)
        return NULL;

    ib = bufnew(READ_UNIT);
    bufgrow(ib, READ_UNIT);
    while ((ret = fread(ib->data + ib->size, 1, ib->asize - ib->size, in)) > 0) {
        ib->size += ret;
        bufgrow(ib, ib->size + READ_UNIT);
    }

    fclose(in);

    ob = bufnew(OUTPUT_UNIT);

    sdhtml_renderer(&callbacks, &options, 0);
    markdown = sd_markdown_new(0, 16, &callbacks, &options);

    sd_markdown_render(ob, ib->data, ib->size, markdown);
    sd_markdown_free(markdown);

    result = malloc(ob->size + 1);
    memcpy(result, ob->data, ob->size);
    result[ob->size] = '\0';

    bufrelease(ib);
    bufrelease(ob);

    return result;
}
