function notEqualAdmin(field, rules, i, options)
{
    if (field.val() == "admin") {
        // this allows the use of i18 for the error msgs
        return 'This field can not be admin!';
    }
}

