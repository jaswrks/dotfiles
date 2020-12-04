#!/usr/bin/env bash

if [[ "${-}" == *i* && -f ~/.profile ]]; then
    . ~/.profile;
fi;
