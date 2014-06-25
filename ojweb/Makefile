TARGET = ojweb
LIBS = -lm
CC = gcc
CFLAGS = -Wall -Wextra -Werror -std=c99 -pedantic -Wmissing-prototypes -Wstrict-prototypes -Wold-style-definition -g -O3

.PHONY: default all clean

default: $(TARGET)
all: default

OBJECTS = $(patsubst %.c, %.o, $(wildcard *.c sundown/*.c))
HEADERS = $(wildcard *.h sundown/*.h)

sundown/%.o: sundown/%.c $(HEADERS)
	$(CC) -g -O3 -c $< -o $@

%.o: %.c $(HEADERS)
	$(CC) $(CFLAGS) -c $< -o $@

.PRECIOUS: $(TARGET) $(OBJECTS)

$(TARGET): $(OBJECTS) $(SUNDOWN)
	$(CC) $(OBJECTS) -Wall $(LIBS) -o $@

clean:
	-rm -f *.o sundown/*.o
	-rm -f $(TARGET)
