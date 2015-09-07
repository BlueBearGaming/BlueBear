server 'bluebear.xyz',
    user: 'vincent',
    roles: %w{web app db},
    ssh_options: {
        user: 'user_name', # overrides user setting above
        forward_agent: false,
        auth_methods: %w(publickey password)
}
