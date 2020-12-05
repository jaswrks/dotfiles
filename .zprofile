#!/usr/bin/env bash

# Customization.

if [[ "${-}" == *i* && -d ~/.zsh ]]; then
	. ~/.zsh/env-path;
	. ~/.zsh/env-vars;
	. ~/.zsh/env-ops;

	. ~/.zsh/options;
	. ~/.zsh/aliases;
	. ~/.zsh/functions;
	. ~/.zsh/prompt;
fi;
