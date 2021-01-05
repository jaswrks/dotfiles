# This follows `.gitignore` exactly. -----------------------------------------------------------------------------------

# Local

.#*
.~*
._*

# Logs

*.log

# Backups

*~
*.bak

# Patches

*.rej
*.orig
*.patch
*.diff

# Vagrant

.vagrant/

# TypeScript

typings/

# IntelliJ

.idea/

# Sublime

*.sublime-project
*.sublime-workspace

# Netbeans

*.nbproject

# VS Code

.vscode/
*.code-workspace

# Vendor

vendor/

# NodeJS

node_modules/

# JSPM

jspm_packages/

# Bower

bower_components/

# Linaria & SASS

.linaria-cache/
.sass-cache/

# Elastic Beanstalk

.elasticbeanstalk/

# CTAGs

*.ctags
*.tags

# VCS

.git/
.git-dir/

.svn/
_svn/

CVS/
.cvsignore

.bzr/
.bzrignore

.hg/
.hgignore

SCCS/
RCS/

# PC Files

$RECYCLE.BIN/
Desktop.ini
Thumbs.db
ehthumbs.db

# Mac Files

.AppleDB
.AppleDouble
.AppleDesktop
.com.apple.timemachine.donotpresent
Network Trash Folder
Temporary Items
.LSOverride
.Spotlight-V100
.VolumeIcon.icns
.TemporaryItems
.fseventsd
.DS_Store
.Trashes
.apdisk
Icon?
!Icons
._*

# These are in addition to what we have in `.gitignore`. ---------------------------------------------------------------

# LFS Storage

/lfs/

# Demos

/demo/
/demos/

# Docs

/doc/
/docs/

# Tests

/test/
/tests/

# Git Dotfiles

/.gitignore
/.gitattributes
/.gitmodules
/.gitchange

# GitHub Misc.

/.github/
/CONTRIBUTING.md
/ISSUE_TEMPLATE.md
/PULL_REQUEST_TEMPLATE.md

# GitHub + Jekyll Dirs/Files
# Note: `docs/` and `assets/` are also used by Jekyll.
# They aren't listed here because they are listed elsewhere.

/_config.yml
/_layouts/
/_includes/
/_sass/

# Bower

/.bowerrc

# NPM

/.npmrc
/.npmignore

# Babel

/.babelrc
/.babelrc.*
/babel.config.*

# Linaria

/.linariarc
/.linariarc.*
/linaria.config.*

# Webpack

/webpack.config.*

# TypeScript

*.ts
*.tsx
!*.d.ts
/types/
/tsfmt.json
/typings.json
/tsconfig.json

# Gulp Files

/gulpfile.js

# Grunt Files

/Gruntfile

# Drone

/.drone.*

# Circle CI

/circle.yml
/.circle.yml

# Travis

/.travis.*
/.travis-*

# Coverage

/.coveralls.*
/.scrutinizer.*

# Composer

/composer.json

# ----------------------------------------------------------------------------------------------------------------------

# <custom>
#   Put your rules in custom comment markers.
# </custom>
