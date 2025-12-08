FROM nginx:stable

# Copy nginx template and entrypoint into the image so runtime substitution works on Render
COPY default.conf.template /etc/nginx/conf.d/default.conf.template
COPY entrypoint.sh /usr/local/bin/nginx-entrypoint.sh
RUN chmod +x /usr/local/bin/nginx-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
