Data Transform Engine
=====================

This project implements a mechanism to wrangle text data from one form to
another. Transformations are specified with a config file on the command line.


## High-level architectural overview ##

The main object that performs the work is an Engine object
(./src/Engine/Core/Engine.php). The Engine requests row data from a Source object,
presents the row to an executable config file (chosen on the command line), and
sends the transformed data to a Sink object. See Engine::run() for the main loop
of the program.

The config file is a standard PHP file which defines the target record format
and how the row data should be transformed. Validations are also supported. See
./config/sample.php as an example.

The Source interface defines an abstraction that facilitates extending the
application to handle additional formats. The application currently only
supports CSV files as a source. See ./src/Engine/Sources/CsvFileSource.php.
Other potential sources could be an XML file, a database, a web service, etc.

The Sink interface defines an abstraction that facilities extending the
application to handle additional destinations for the transformed data.
Currently, only echoing the transformed data in a CSV format is supported. See
./src/Engine/Sinks/CsvStdoutSink.php. Other potential sinks that could be
written are ones that send the data to a database, a log file, a web service,
etc.

The vocabulary allowed in the config file is contained in
./src/Engine/ConfigVocabulary. The vocabulary allowed depends on the current
type of the value, where all values coming from the source initially begin as
SourceValues (./src/Engine/Core/ValueTypes/SourceValue.php). SourceValues
remember the location where they originated in the source document to help with
error messages. From there, transforms can be applied to convert them into other
types and also validated.


## Extension points ##

- Add config files: place in ./config.
- Add a source: implement Source interface and place in ./src/Engine/Sources.
- Add a sink: implement Sink interface and place in ./src/Engine/Sinks.
- Add vocabulary to be used in config files. Identify the type of value the
  new vocabulary should be used with and locate the corresponding file under
  ./src/Engine/ConfigVocabulary. You may need to create new files. Look under
  String for example Validators and Transforms files. Look under Date for an
  example Tags file (these are used to generate new values from a combination
  of other values). In this case, since Tags files are not autoloaded, you
  will need to add new files to the top of Engine.php. Look there for an
  example. For transforms and validators, if you create a new file, you will
  need to also add a "use" statement to add the trait to the appropriate
  class under ValueTypes. (Traits were used just as an easy way to group all
  the vocabulary under one directory, not for code reuse.)
- Add new types of values: extend AbstractValue and place in
  ./src/Engine/Core/ValueTypes.


## Usage ##

A Dockerfile has been provided to build a PHP environment that can run the
application.

First, you need to build an image from the docker file. In the
data-transform-engine directory, run:

```bash
docker build -t wrangler .
```

To run the application against the default sample.csv file, run:

```bash
docker run --rm -it -v $PWD/data:/var/www/html/data wrangler
```

If you want to provide your own config files, you can place them in ./config.
Additionally, if you want to provide your own CSV files, you can place them in
./data. Then run the following, except replace the "sample" config with your own
and the sample.csv file with your own.

```bash
docker run --rm -it \
  -v $PWD/data:/var/www/html/data \
  -v $PWD/config:/var/www/html/config \
  -v $PWD/log:/var/log wrangler \
  php engine.php --config sample ./data/sample.csv
```

The error log is located at ./log/errors.log.

To execute the unit tests, run:

```bash
docker run --rm -it wrangler ./vendor/bin/phpunit ./test
```


## Assumptions and simplifications

- Config files are being maintained by trusted users (since they are executable 
  code).
- The first row of a CSV file is always a header row. Blank rows are supported,
  but not for the first row.
- The CSV file contains only ASCII characters.
- The desired output format is also CSV.
- The sample.php config file was written to show validations you might not care
  about (e.g. ensuring the Month column has exactly 2 digits (left-padded with
  zero). These validations would halt execution of the row if they failed.
- To make the CSV output easier, the DateValue internally represents the value
  as a string, even though PHP has a DateTime class.
- The patterns provided in the spec are strictly accurate, e.g. "[A-Z]+" means
  all uppercase letters (and no spaces).
- Decimal values aren't picky about where commas appear in the input string.


## Next steps ##

- Add docblock comments where they are missing.
- Add additional vocabulary to enable more validations and transformations in
  the config files.
- Add implementations of additional sources and sinks.
- Update the CLI to allow choosing the source and sink.
- Consider defining higher-level vocabulary that internally performs validations
  and transformations to simplify the config files.
- Consider providing an ability for validations to be soft-validations (i.e.
  don't stop processing the record, but just log a warning message).
- Consider adding the ability to collect all the errored records in a separate
  file to be manually fixed and then loaded at once.
- Figure out a good solution for logging. Right now, the application logs to
  php.log, but perhaps it would be better to send it to stderr instead of
  mounting a directory for the logs.
- Refactor the code to use a logger to make it easier to test logging messages.


  

TODO view logs... use docker logs or mount a volume?
TODO add phpunit
TODO add tests
TODO add way to run the tests.
TODO deliver!

