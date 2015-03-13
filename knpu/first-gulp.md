# Your First Gulp

Hey guys! Yes! In this tutorial, we get to play with Gulp! And that's got
me excited for 3 reasons. First, it has an adorable name. Second, it's going
to solve real problems - like processing SASS into CSS, minifying files and
making you a delicious cup of coffee. Yes, I *am* lying about the coffee,
unless your coffee machine has an API. Then I guess it *would* be possible.
Ok, and third, Gulp is refreshing to work with... and just a lot of fun.

On the surface, Gulp is just a way to make command-line scripts. But its
secret ingredient is a *huge* plugin community that'll give us free tools.
The Gulp code you'll write will also be really hipster, since you'll write
it all in Node.js. But remember, that just means it's JavaScript that's run
on the server like PHP.

## Laravel Elixir

The Laravel folks like Gulp so much, they created a tool on top of it called
[Elixir](http://laravel.com/docs/5.0/elixir). If you check out its docs,
Elixir lets you process SASS files, LESS files, CoffeeScript, minimize things
and other frontend asset stuff. It's really cool.

The problem with Elixir is that, one, it's meant to only work with Laravel.
And two, it's a little too magic for my taste. So in this tutorial, we're
going to get all the sweetness of Elixir, but build it so that you understand
what's going on. Then, you'll be able to bend and make it do whatever you
want. Like, make you a some coffee.

## Installing the Gulp Command

So let's install our new toy: Gulp! Head to the terminal, and type
`sudo npm install -g gulp`. The Node Package Manager - or npm - is the Composer
of the Node.js world. If you get a "command not found", go install
[Node.js](https://nodejs.org/download/), it comes with `npm` and it's *totally*
worth it.

This command will give us a `gulp` executable. Take this Node executable
for a test-drive by typing `gulp -v`:

```bash
gulp -v
````

## Bootstrapping package.json

We know that Composer works by reading a `composer.json` file and downloading
everything into a `vendor/` directory. Great news! `npm` does the same thing.
It reads from a `package.json` and downloads everything into a `node_modules`
directory. To get a shiny new `package.json` type `npm init`. Hit enter to
get through the questions - they don't matter for us.

And there's the shiny new file I promised. Right now, it's boring:

[[[ code('5b71430aa1') ]]]

## Installing Gulp into your Project

But not we can install Node packages into our project. The first, is `gulp`!
So type, `npm install gulp --save-dev`. Wait, didn't we already install this?
Well, the original command - with the `-g` gave us the global `gulp` executable.
This actually puts it *inside* our project so other libraries can use it.
Don't forget the `--save-dev` part. That says, "download this into my project
AND add an entry into `package.json` for it."

Great! Flip back to your editor. The `package.json` has a new `devDependencies`
section *and* we have a new `node_modules` directory with `gulp` in it:

[[[ code('072ba2f64d') ]]]

In Composer terms, `devDependencies` is the `require` key in `composer.json`
and `node_modules` is the `vendor/` directory. Ok, we're rocking some Node!

## Our First Gulp (Task)

Time to work. Create a new file - `gulpfile.js` - at the root of your project.
Gulp looks for this. Next, flex your Node skills and say
`var gulp = require('gulp');`. Below this, we'll define tasks. Let's create
a task called `default`. The idea is simple. This says, when I execute the
task called `default`, I want you to execute this function. Use the good
ol' `console.log` to test things!

[[[ code('fdf955cbf5') ]]]

Guys, these 5 lines are a fully-functional Gulp file. Head back to the command
line and type `gulp` followed by the name of the task: `default`:

```bash
gulp default
```

It's alive! We can also just type `gulp` and get the same thing. The task
called `default` is... well, the "default" task and runs if you don't include
the name.

Now let's process some SASS files.
