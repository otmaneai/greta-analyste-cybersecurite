FROM wordpress:php8.3-apache

COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY wp-content/themes/greta-analyste-cybersecurite /usr/src/wordpress/wp-content/themes/greta-analyste-cybersecurite
COPY wp-content/plugins/greta-learning-platform /usr/src/wordpress/wp-content/plugins/greta-learning-platform
COPY project-data /usr/src/wordpress/project-data
COPY pdfs_context /usr/src/wordpress/wp-content/uploads/greta-resources

RUN mkdir -p /usr/src/wordpress/wp-content/uploads/greta-resources \
	&& chown -R www-data:www-data /usr/src/wordpress/wp-content /usr/src/wordpress/project-data
