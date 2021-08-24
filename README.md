# keyword-checker
This script lets you fetch websites rankings from google search engine using list of keywords via command line or url.

Script was made only for educational purpose, use it on your own risk.

## Getting Started

### Prerequisites
Things you need to install.
- [PHP](https://www.php.net/downloads.php) (tested on PHP 8.0.7)


### Executing script
Script can be executed via **command line** or **url**

#### Executing via command line
commands examples:
```
php keyword_checker.php "web design,online shopping"
php keyword_checker.php "programming" "https://www.google.com/search?num=100&q="
php keyword_checker.php "scripting" "https://www.google.com/search?num=10&q=" "My useragent"
```
- **param[0]** - script file name and location, in this case: _keyword_checker.php_
- **param[1]** - list of keywords separated by comma
- **param[2]** (optional) - google search query url, you can add additional params, localisation etc...
- **param[3]** (optional) - custom user agent name, sometimes websites restrict specific useragents or allow them

#### Executing via url
You can place script in web server and execute via url for example:
```
http://localhost/keyword_checker.php?keywords=web+design,online+shopping
http://localhost/keyword_checker.php?keywords=web+design,online+shopping&search_url=https%3A%2F%2Fwww.google.com%2Fsearch%3Fnum%3D100%26q%3D
http://localhost/keyword_checker.php?keywords=web+design,online+shopping&search_url=https%3A%2F%2Fwww.google.com%2Fsearch%3Fnum%3D10%26q%3D&useragent=my+useragent
```
- **keywords** - list of keywords separated by comma
- **search_url** (optional) - google search query url, you can add additional params, localisation etc...
- **useragent** (optional) - custom user agent name, sometimes websites restrict specific useragents or allow them

**IMPORTANT:** Do not forget to encode url params using [url encode](https://meyerweb.com/eric/tools/dencoder/)

### Result
On success script returns data in json format for example:
```json
[
   {
      "fetch_url":"https://www.google.com/search?num=10&q=programming",
      "keyword":"programming",
      "positions":[
         "https://en.m.wikipedia.org/wiki/Computer_programming",
         "https://en.m.wikipedia.org/wiki/Programming_language",
         "https://www.snhu.edu/about-us/newsroom/2018/06/what-is-computer-programming",
         "https://hackr.io/blog/what-is-programming",
         "https://www.futurelearn.com/info/courses/programming-101/0/steps/43783",
         "https://www.edx.org/learn/computer-programming",
         "https://www.python.org/",
         "https://m.youtube.com/watch?v=zOjov-2OZ0E",
         "https://m.youtube.com/watch?v=CIRGjwYgdT4"
      ],
      "error":""
   },
   {
      "fetch_url":"https://www.google.com/search?num=10&q=golang",
      "keyword":"golang",
      "positions":[
         "https://golang.org/",
         "https://en.m.wikipedia.org/wiki/Go_(programming_language)",
         "https://m.youtube.com/watch?v=YS4e4q9oBaU",
         "https://github.com/golang/go",
         "https://mobile.twitter.com/golang",
         "https://go.dev/",
         "https://pkg.go.dev/std"
      ],
      "error":""
   }
]
```
- **fetch_url** - full url used to fetch data from.
- **keyword** - keyword.
- **positions** - website rankings where first item in array is first result in search engine.
- **error** - error message.