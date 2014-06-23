TARGET = ojweb
LIBS = -lm
CC = gcc
CFLAGS = -Wall -Wextra -Werror -std=c99 -pedantic -Wmissing-prototypes -Wstrict-prototypes -Wold-style-definition -g -O3

.PHONY: default all clean

default: $(TARGET)
all: default

OBJECTS = $(patsubst %.c, %.o, $(wildcard *.c))
HEADERS = $(wildcard *.h)

%.o: %.c $(HEADERS)
	$(CC) $(CFLAGS) -c $< -o $@

.PRECIOUS: $(TARGET) $(OBJECTS)

$(TARGET): $(OBJECTS)
	$(CC) $(OBJECTS) -Wall $(LIBS) -o $@

clean:
	-rm -f *.o
	-rm -f $(TARGET)
