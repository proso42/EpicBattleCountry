let rdm_name = "";
let con = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z'];
let voy = ['a', 'e', 'i', 'o', 'u', 'y'];
let max = get_rdm_int(4);
if (max == 0)
    max = 1
while (max > 0)
{
    let rdm = get_rdm_int(3);
    if (rdm == 0)
    {
        // -- Tech 1 -- Con Voy Con
        let lettre = con[get_rdm_int(20)];
        while (lettre == rdm_name[rdm_name.length - 1])
            lettre = con[get_rdm_int(20)];
        rdm_name = rdm_name.concat(lettre, voy[get_rdm_int(6)], con[get_rdm_int(20)]);
    }
    else if (rdm == 1)
    {
        // -- Tech 2 -- Voy Con Con
        let syl = "";
        let lettre = voy[get_rdm_int(6)];
        while (lettre == rdm_name[rdm_name.length - 1])
            lettre = voy[get_rdm_int(6)];
        syl = syl.concat(lettre, con[get_rdm_int(20)]);
        lettre = con[get_rdm_int(20)];
        while (lettre == syl[syl.length - 1])
            lettre = con[get_rdm_int(20)];
        rdm_name = rdm_name.concat(syl, lettre);
    }
    else
    {
        // -- Tech 3 -- Con Con Voy
        let lettre = con[get_rdm_int(20)];
        while (lettre == rdm_name[rdm_name.length - 1])
            lettre = con[get_rdm_int(20)];
        let lettre2 = con[get_rdm_int(20)];
        while (lettre == lettre2)
            lettre2 = con[get_rdm_int(20)];
        rdm_name = rdm_name.concat(lettre, lettre2, voy[get_rdm_int(6)]);
    }
    max--;
}
rdm_name = strUcFirst(rdm_name);
console.log(rdm_name);

function get_rdm_int(i)
{
    return Math.floor(Math.random() * Math.floor(i));
}

function strUcFirst(a){return (a+'').charAt(0).toUpperCase()+a.substr(1);}