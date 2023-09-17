# CLI application for generating JSON from a CSV file

*gentree* is a command-line tool that converts CSV files into JSON format. It helps you transform data quickly and easily, making it useful for various tasks like web development and data analysis. Just run a few commands, and your CSV data becomes structured JSON data.

Key Features:

- Converts CSV to JSON.
- Easy-to-use command-line interface.
- Works with PHP 8.1+.
- Supports Docker and non-Docker setups.

It simplifies data conversion for your projects.

## Requirements

- PHP >= 8.1
- Composer

## Installation

### Using Docker

1. Clone this repository

```sh
git clone git@github.com:arzamaskov/gentree.git
```
2. Build the Docker image

```sh
make build
```
3. Install all dependencies:

```sh
make install
```

### Without Docker

1. Clone the repository and then run

```sh
composer install
```

## Usage

Run the following commands based on your setup

### Using Docker

Start the container:

```sh
make run
```
Inside the container, execute:

```sh
gentree generate /path/to/CSV-input-file /path/to/JSON-output-file
```

### Without Docker

Simply run:

```sh
bin/gentree generate /path/to/CSV-input-file /path/to/JSON-output-file
```

This will create a JSON file based on your CSV data.
