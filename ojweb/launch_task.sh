#!/bin/bash

# exit on error
set -e

# exit on uninitialized variable
set -u

# show (-x) or hide commands (+x)
set +x

# system-dependent configurations
export CAT="/usr/local/bin/gcat"
export CXX="/usr/bin/g++"
export DIFF="/usr/bin/diff"
export GREP="/usr/local/bin/ggrep"
export RM="/usr/local/bin/grm"
export TIME="/usr/local/bin/time"
export TIMEOUT="/usr/local/bin/gtimeout"
export WGET="/usr/local/bin/wget"

./task.sh
