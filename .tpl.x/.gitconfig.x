[user]
	name = Jason Caldwell
	email = jaswrks@o5p.me
  url = https://jaswrks.com
[init]
  defaultBranch = main
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
[filter "lfs"]
  required = true
	clean = git-lfs clean -- %f
	smudge = git-lfs smudge -- %f
	process = git-lfs filter-process
