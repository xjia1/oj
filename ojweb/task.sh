#!/bin/bash

# exit on error
set -e

# exit on uninitialized variable
set -u

# show (-x) or hide commands (+x)
set +x

# system-dependent configurations
CAT="/usr/local/bin/gcat"
CXX="/usr/bin/g++ -std=c++11 -O3"
DIFF="/usr/bin/diff -q"
GREP="/usr/local/bin/ggrep"
RM="/usr/local/bin/grm"
TIME="/usr/local/bin/time"
TIMEOUT="/usr/local/bin/gtimeout -s 9"
WGET="/usr/local/bin/wget"

# web-dependent configurations
OJ="http://localhost:9876"

# ensure the code is present
CODE="0166ce9ae26a53bfb8e84105c20bce0320c23362.cpp"
if [ ! -f $CODE ]; then $WGET "$OJ/fetch/code/$CODE"; fi

# remove previous executable (if any)
$RM -f a.out

# compile the code
set +e
$TIMEOUT 10s $CXX $CODE 2>compile.log
RV=$?
set -e

# if no executable is generated
if [ ! -f a.out ]; then
    # if timeout returns 137 = 128 + 9 = SIGKILL
    if [ "$RV" -eq 137 ]; then
        echo "compile time limit exceeded"
    else
        echo "compile error"
        $CAT compile.log
    fi

    # do not proceed since there is nothing to execute
    exit
fi

INPUT="32d3c3db7c8157c3bb576fa546eb6e41322fb89e"
ANSWER="27cc6f77ee63df90ab3285f9d5fc4ebcb2448c12"
TIME_LIMIT="0.5s"
MEMORY_LIMIT="6553600" # kbytes

# ensure input and answer files are present
if [ ! -f $INPUT ]; then $WGET "$OJ/fetch/data/$INPUT"; fi
if [ ! -f $ANSWER ]; then $WGET "$OJ/fetch/data/$ANSWER"; fi

# execute student program
set +e
$TIMEOUT $TIME_LIMIT $TIME -v -o time.log ./a.out <$INPUT 1>output 2>/dev/null
RV=$?
set -e

# get time usage
TIME_USE=$($GREP "wall clock" time.log | $GREP -o -P "\d[:\d\.]+")
# get memory usage
RSS=$($GREP "Maximum resident set size" time.log | $GREP -o -P "\d+")

if [ "$RV" -eq 137 ]; then
    echo "time limit exceeded"
elif [ "$RV" -ne 0 ]; then
    echo "runtime error"
elif [ "$RSS" -gt "$MEMORY_LIMIT" ]; then
    echo "memory limit exceeded"
elif [ ! -f output ]; then
    echo "no output"
else
    # compare output to answer
    set +e
    $DIFF -B --strip-trailing-cr output $ANSWER 1>/dev/null 2>&1
    RV=$?
    set -e

    if [ "$RV" -eq 0 ]; then
        echo "accepted $TIME_USE $RSS";
    else
        # compare again, ignore cases and whitespaces
        set +e
        $DIFF -i -w -B --strip-trailing-cr output $ANSWER 1>/dev/null 2>&1
        RV=$?
        set -e

        if [ "$RV" -eq 0 ]; then
            echo "presentation error"
        else
            echo "wrong answer";
        fi
    fi
fi
