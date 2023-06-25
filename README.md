# DBSocket

## "What is?"
 <h3>
    DBSocket is a composer metapackage that provides secure database connection and manipulation.
 </h3>
 
 ## "Why?"
 
<p>
    The package was conceived in the need to speed up the development of projects in <b>PHP</b>,
       more precisely focused on "skipping" the step of creating a program to manipulate
        project database. For this reason, DBSocket provides a simple and intuitive interface.
        collection for you to use.
</p>

### Installation

<h4>
 To install the package in your project use the following command: <code>composer require alanjoose/db-socket</code>.
</h4>

<h4>
 Then run the command: <code>composer install</code>.
</h4>

### Configuration

<h4>
 The package configuration is done through the project's environment variables. Here's an example of how to do it using native PHP variables:
</h4>

<img src="https://github.com/Alanjoose/db-socket/blob/master/docs/env_vars_example.png"/>

<h4>
 But there is another form of configuration, which I particularly recommend; which is the use of the <a href="https://github.com/vlucas/phpdotenv"><b>vlucas/phpdotenv</b></a> package, follow the example:
</h4>

<img src="https://github.com/Alanjoose/db-socket/blob/master/docs/dot_env_example.png"/>

### Basic usage

<h4>
 The <b>DB</b> class is the main package facade, use this to handle your database.
</h4>

#### Example

##### Basic methods structure

<code>DB::[method]($query, ?$params);</code>

<code>DB::insert('insert into users(name, email, password) values ("Admin", "admin@example.net", "password")');</code>

### Binds support

<code>DB::insert('insert into users(name, email, password) values (?, ?, ?)', ['admin', 'admin@example.net', 'password']);</code>

### Query security

<span>
 If you call this statement an <b>MissingQueryStatementException</b>. It occurs because the query doesn't conatains the 'where' statement section.
</span>

<code>DB::update('update users set name = ?', ['user']);</code>

<span>
 But if you still want to run this, you must to add the 3º param $ignoreWhere = true.
</span>

<code>DB::update('update users set name = ?', ['user'], true);</code>

<small>
 Same style for <code>DB::delete()</code>.
</small>

### Special routines

<span>
You also can use <code>DB::clearTable($table)</code>;<br>
 If you want to truncate a table, use <code>DB::trucateTable($table, $disableForeignkeyChecks);</code>
</span>

### Transactions support

<span>
The <b>db-socket</b> also provides transactions support.
</span>

#### Basic structure
<code>DB::runTransaction(Closure $callback, $retrysCaseDeadlock = 3);</code>

<code>DB::runTransaction(function() use ($user){
  DB::update('update users set email_verified_at = ? where id = ?', [date('Y-m-d H:i:s'), $user->id]);
  DB::update('update jobs set run_interval = ? where target = ?', ['daily', 'verify_email']);
});</code>

### Conclusion

<span>
As you can see, it's a simple package to use. Feel free to explore it and speed up the development of your projects. I hope it's useful to you. Enjoy :D
</span>
