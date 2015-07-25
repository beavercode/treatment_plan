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
    - php directives
        - file_uploads = On
        - memory_limit >= 30MB

2. Browsers
    - Internet Explorer 9 and above


##TODO
1. Prevent form resubmitting, but allow user to see fully filled form for further correction (BE).
2. Save doctors photo to DB instead of "<web_root>/doctors"?
3. Make transliterate package for resulting pdf names (BE). 

##Bugs
1. Form: input[file] popover do not changes for same file (FE)
2. Form: input[file] do not saves state after submitting a form (BE)
3. Form: input[file] modified be BS File input has bug with focus when tabbing (FE)

ps. FE - front-end, BE - back-end.
