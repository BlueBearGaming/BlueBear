# Require any additional compass plugins here.

# Set this to the root of your project when deployed:# Set this to the root of your project when deployed:
common_dir = "src/BlueBear/BackofficeBundle/Resources/"

http_path = "web"
sass_dir = common_dir + "sass"
images_dir = common_dir + "public/images"

css_dir = common_dir + "public/css"
generated_images_dir = common_dir + "public/images"
javascripts_dir = common_dir + "public/js"

relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false
environment = :dev

if environment == :production
  output_style = :compressed
else
  output_style = :expanded
  sass_options = { :debug_info => true }
end

add_import_path "web/bundles";
add_import_path "vendor/log0ymxm/bootswatch-scss";