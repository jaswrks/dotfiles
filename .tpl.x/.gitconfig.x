[user]
	name = Jason Caldwell
	email = jaswrks@o5p.me
  url = https://jaswrks.com
	signingkey = D6125FF6
[core]
  eol = lf
  autocrlf = input
  ignorecase = true
  filemode = true
  excludesfile = ~/.gitignore
  editor = vim
[push]
  default = simple
[pull]
	rebase = true
[color]
  ui = auto
  diff = auto
  status = auto
  branch = auto
  interactive = auto
[credential]
  helper = store
[alias]
  # See: `~/.bin/git` for others.
  aliases = !git config --get-regexp 'alias.*' | colrm 1 6 | sed 's/[ ]/ = /'
[filter "lfs"]
  required = true
	clean = git-lfs clean -- %f
	smudge = git-lfs smudge -- %f
	process = git-lfs filter-process
[commit]
	gpgsign = true
