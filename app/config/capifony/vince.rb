set :domain,               "bluebear.xyz"
set :deploy_to,            "/home/www/bluebear"
set :app_path,             "app"
set :user,                 "vincent"
set :branch,               "master"
#ssh_options[:port] =       "22"

set :webserver_user,       "www-data"

role :web,                 domain
role :app,                 domain
role :db,                  domain, :primary => true

logger.level = Logger::IMPORTANT
#logger.level = Logger::MAX_LEVEL