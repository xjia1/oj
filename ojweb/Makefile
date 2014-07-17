TARGET = ojweb
LIBS = -lm
CC = clang
CFLAGS = -std=gnu99 -Wall -Wextra -Werror -pedantic -Wmissing-prototypes -Wstrict-prototypes -Wold-style-definition -g -O3

.PHONY: default all clean

default: $(TARGET)
all: default

OBJECTS = $(patsubst %.c, %.o, $(wildcard *.c sundown/*.c rfc3174/*.c))
HEADERS = $(wildcard *.h sundown/*.h rfc3174/*.h)

sundown/%.o: sundown/%.c $(HEADERS)
	$(CC) -g -O3 -c $< -o $@

%.o: %.c $(HEADERS)
	$(CC) $(CFLAGS) -c $< -o $@

.PRECIOUS: $(TARGET) $(OBJECTS)

$(TARGET): $(OBJECTS) $(SUNDOWN)
	$(CC) $(OBJECTS) -Wall $(LIBS) -o $@

clean:
	-rm -f *.o */*.o
	-rm -f $(TARGET)
