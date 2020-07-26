#rename [ _ *
#rename ] _ *

find . -type f -name "* *" -print |
while read name; do
na=$(echo $name | tr '_KxIX_Shuumatsu_Nani_Shitemasuka_Isogashii_Desuka_Sukutte_Moratte_Ii_Desuka_' '_KxIX_Shuumatsu Nani Shitemasuka Isogashii Desuka Sukutte Moratte Ii Desuka ')
if [[ $name != $na ]]; then
mv "$name" $na
fi


done