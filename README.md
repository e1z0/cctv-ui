# Short desciption

A web ui that is designed to view camera recordings and snapshots from [motion](https://motion-project.github.io) and [frigate](https://github.com/blakeblackshear/frigate) combined with [telegram-cctv](https://github.com/e1z0/telegram-cctv) is a great home solution for CCTV.

# Long description

It was designed long before [frigate](https://github.com/blakeblackshear/frigate) has built in abillity to view snapshots or record clips. It was designed for home use only with 1 to 10 cameras. It have abillity to easily navigate the interface with keyboard shortcuts or mouse. You can add clips/snapshots to favorites, make a comment view chart statistics depending on detected object and many more.


# Installation

Put files in **/var/www/html/video** edit **config.php** fill details, provided sample should help with that. Configure motion with mysql database as described in [telegram-cctv](https://github.com/e1z0/telegram-cctv) installation guide and install it also, it will provide interface with frigate snapshots. Add to crontab:
```
*/10 * * * * root php /var/www/html/video/crontab.php --regular
0 * * * * root php /var/www/html/video/crontab.php --hour
0 0 * * * root php /var/www/html/video/crontab.php --day
0 0 * * 0 root php /var/www/html/video/crontab.php --week
0 0 1 * * root php /var/www/html/video/crontab.php --month
0 0 1 1 * root php /var/www/html/video/crontab.php --year

```

# TODO

This project is provided for achiving purposes as it will be rewritten in golang to support frigate latest new builtin features.

# Screens

![](/pics/Screenshot%202021-06-14%20at%2009.02.23.png)
![](/pics/Screenshot%202021-06-14%20at%2009.03.30.png)
![](/pics/Screenshot%202021-06-14%20at%2009.03.44.png)
![](/pics/Screenshot%202021-06-14%20at%2009.04.10.png)
