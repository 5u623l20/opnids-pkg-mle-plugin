#!/usr/local/bin/python2.7

"""
    Copyright (c) 2018 Max Orelus <mo@counterflowai.com>
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:

    1. Redistributions of source code must retain the above copyright notice,
     this list of conditions and the following disclaimer.

    2. Redistributions in binary form must reproduce the above copyright
     notice, this list of conditions and the following disclaimer in the
     documentation and/or other materials provided with the distribution.

    THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
    AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
    AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
    OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.

    --------------------------------------------------------------------------------------
    get file and tail the file by x lines
"""

import sys
import ast
import json
import base64

if len(sys.argv) !=3:
    print 'Usage: get_stats.py <filename> <nlines>'
    sys.exit(1)

filename, nlines = sys.argv[1:]
num_lines = int(nlines)

file_line_count = sum(1 for line in open(filename))

if num_lines > file_line_count:
    num_lines = file_line_count

with open(filename) as f:
    content = f.read().splitlines()

count = len(content)
ar = []
for i in range(count-num_lines,count):
  ar.append(ast.literal_eval(content[i].strip()))

json_string = json.dumps(ar)

print base64.b64encode(json_string)