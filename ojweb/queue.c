#include "queue.h"
#include <stdlib.h>

struct node
{
    queue_element_t value;
    struct node *next;
};

struct queue
{
    struct node *head;
    struct node *tail;
};

queue_t queue_create(void)
{
    return calloc(1, sizeof(struct queue));
}

int queue_is_empty(queue_t q0)
{
    struct queue *q = q0;
    return q->head == NULL && q->tail == NULL;
}

void queue_push(queue_t q0, queue_element_t x)
{
    struct node *n = malloc(sizeof(struct node));
    n->value = x;
    n->next = NULL;

    struct queue *q = q0;
    if (queue_is_empty(q))
    {
        q->head = q->tail = n;
    }
    else
    {
        q->tail->next = n;
        q->tail = n;
    }
}

queue_element_t queue_pop(queue_t q0)
{
    struct queue *q = q0;
    if (queue_is_empty(q))
    {
        return 0;
    }
    else
    {
        queue_element_t result = q->head->value;
        q->head = q->head->next;
        return result;
    }
}
