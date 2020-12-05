#!/usr/bin/env bash

if [[ "${-}" == *i* && -d ~/.bash/profile ]]; then
    . ~/.bash/profile/env-path;
    . ~/.bash/profile/env-vars;
    . ~/.bash/profile/env-ops;

    . ~/.bash/profile/options;
    . ~/.bash/profile/aliases;
    . ~/.bash/profile/functions;
    . ~/.bash/profile/prompt;
fi;
