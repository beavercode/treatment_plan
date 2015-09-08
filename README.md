#Treatment user information (UTI)


##Intention

Create stable and useful system...


##Requirements

1. Php
    - 5.4 and above
    - php extensions
        - dom
        - gd
        - pdo
        - pdo_sqlite
        - sqlite3
        - mbstring
        - xsl
        - zip
    - php directives
        - file_uploads = On
        - memory_limit >= 30MB

2. Web-server: Apache2, Nginx

3. Browsers
    - Internet Explorer 9 and above


##TODO
0. Posibility to save and restore form state. (BE)
1. Prevent form resubmitting, but allow user to see fully filled form for further correction (BE). Related to 0. (BE)
1. Universal package for working with DB(sqlite,mysql,etc.). (BE)
2. Add posibility to add/edit/remove doctors and their info(name, photo, position). (BE)
3. Save doctors photo to DB instead of "<web_root>/doctors"? (BE, FE)
4. Add posibility add/edit/remove stages(name, pdf_info_files, default_duration). Only for admins? Uses ACL? (BE)
5. Add ACL for better user managment. Roles: supper, usual (BE).
6. Make transliterate package for resulting pdf names (BE). 
7. Replace progress bar with percents(%) (BE, FE).

##Bugs
1. Form: input[file] popover do not changes for same file (FE)
2. Form: input[file] do not saves state after submitting a form (BE)
3. Form: input[file] modified be BS File input has bug with focus when tabbing (FE)

*Legend*: FE - front-end, BE - back-end.
