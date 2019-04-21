FROM ruby:2.4-alpine

# install capistrano
RUN gem install --no-document capistrano -v 3.11.0
RUN gem install --no-document capistrano-symfony -v 1.0.0.rc3

WORKDIR /source

CMD ["cap"]
