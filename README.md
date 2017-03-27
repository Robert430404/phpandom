### What is PHPandom?

This is a simple, random number based, raffle drawing script written for use by the Las Vegas PHP Meetup.

### How does it work?

You have two commands available to you with the script. You can set your raffle ticket number range, and you can draw a number.

Setting Range (Integers are expected and enforced in the script):

    php phpandom.php 1 100

Drawing A Number:

    php phpandom.php

The script serializes your number range, and stores it in a file in the same folder as the script. When you draw a number, it then retrieves that serialized blob, de-serializes it, draws a number and removes it from the blob, and then re-serializes the new array after resetting the keys and re-stores it in the file.
