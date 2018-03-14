#!/usr/bin/env python
import cchardet as chardet
import sys


with open(sys.argv[1], 'r') as content_file:
    content = content_file.read()

print chardet.detect(content)['encoding']