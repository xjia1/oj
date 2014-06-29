#include <stdint.h>

typedef void *queue_t;
typedef void *queue_element_t;

queue_t queue_create(void);
int queue_is_empty(queue_t q);
void queue_push(queue_t q, queue_element_t x);
queue_element_t queue_pop(queue_t q);
