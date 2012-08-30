In order to communicate with the markpetplace, a certificate is required to encrypt the transmitted user information.

You can generate one by executing the following command:

    openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out cert.crt

The private key is only required on the marketplace. The plugin needs only the certificate.