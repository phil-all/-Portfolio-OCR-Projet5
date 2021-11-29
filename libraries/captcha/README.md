# Captcha library

## Table of content

-   [Working principle](#working-principle)

-   [Image Class](#image-class)

-   [Captcha class](#captcha-class)

## Working principle

1.  When page containing form with captcha is called, a `random number` is generated as well as an `image` of this number and a `hash` of this number is stored in a token.

2.  Image is loaded in the form, and user have to enter in a field numbers from image, to valid the form.

3.  During form test process, the token hash is compared to the hash of the number sent by user.

4.  To strengthen bots obstruction, it advisable to put a hidden field with empty default value in form and check it stay empty during test process. Because bots automatically fill all form fields.

## Image class

Used to generate image from the random number.

## Captcha class

Used to generate **random number** and its **hash**.

### Random number

It will be a five digits number between 00001 and 99999.

### Hash

To obtain hash, the *random number* is manipulated as follow :

1.  Number is reverse.

2.  This new number is converted in hexadecimal.

3.  This hexa value is encrypt with a noon reversible way to obtain hash.