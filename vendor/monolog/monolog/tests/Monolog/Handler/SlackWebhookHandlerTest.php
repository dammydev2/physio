# Contributing to Carbon

## Issue Contributions

Please report any security issue using [Tidelift security contact](https://tidelift.com/security).
Tidelift will coordinate the fix and disclosure.
Please don't disclose security bugs publicly until they have been handled by us.

For any other bug or issue, please click this link and follow the template:
[Create new issue](https://github.com/briannesbitt/Carbon/issues/new)

You may think this template does not apply to your case but please think again. A long description will never be as
clear as a code chunk with the output you expect from it (for either bug report or new features).

## Code Contributions

Fork the [GitHub project](https://github.com/briannesbitt/Carbon) and download it locally:

```shell
git clone https://github.com/<username>/Carbon.git
cd Carbon
git remote add upstream https://github.com/briannesbitt/Carbon.git
```
Replace `<username>` with your GitHub username.

Then, you can work on the master or create a specific branch for your development:

```shell
git checkout -b my-feature-branch -t origin/master
```

You can now edit the "Carbon" directory contents.

Before committing, please set your name and your e-mail (use the same e-mail address as in your GitHub account):

```shell
git config --global user.name "Your Name"
git config --global user.email "your.email.address@example.com"
```

The ```--global``` argument will apply this setting for all your git repositories, remove it to set only your Carbon
fork with those settings.

Now you can commit your modifications as you usually do with git:

```shell
git add --all
git commit -m "The commit message log"
```

If your patch fixes an open issue, please insert ```#``` immediately followed by the issue number:

```shell
git commit -m "#21 Fix this or that"
```

Use git rebase (not git merge) to sync your work from time to time:

```shell
git fetch origin
git rebase origin/master
```

Please add some tests for bug fixes and features (so it will ensure next developments will not break your code),
then check all is right with phpunit:

Install PHP if you haven't yet, then install composer:
https://getcomposer.org/download/

Update dependencies:
```
./composer.phar update
```

Or if you installed composer globally:
```
composer update
```

Then call phpunit:
```
./vendor/bin/phpunit
```

Make sure all tests succeed before submitting your pull-request, else we will not be able to merge it.

Push your work on your remote GitHub fork with:
```
git push origin my-feature-branch
```

Go to https://github.com/yourusername/Carbon and select your feature branch. Click the 'Pull Request' button and fill
out the form.

We will review it within a few days. And we thank you in advance for your help.

## Versioning

### Note about Semantic Versioning and breaking changes

As a developer, you must understand every change is a breaking change. What is a bug for someone
is expected in someone else's workflow. The consequence of a change strongly depends on the usage.
[Semantic Versioning](https://semver.org/) relies to public API. In PHP, the public API of a class is its public
methods. However, if you extend a class, you can access protected metho