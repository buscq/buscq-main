# buscq.com
Real-time data from Santiago de Compostela buses

### Usage notes

Add this to your Lighttpd config for proper redirections

```
url.rewrite-final = (
    "^/lineas/(.*)" => "/lineas/index.php?linea=$1",
    "^/parada/(.*)" => "/parada/index.php?parada=$1"
)
```

Also, replace the Google Maps Api Key in [var.php](var.php)

### Contributors
 *  [@mfpousa](https://github.com/mfpousa)
 *  [@ResonantWave](https://github.com/ResonantWave)

### Contributing
 *  The code is licensed under the [GPL v3.0](LICENSE)
 *  Feel free to contribute to the code

### Acknowledgements
 * Thanks to [Tussa](http://tussa.org) for the bus data

