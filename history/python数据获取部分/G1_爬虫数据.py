import requests
from bs4 import BeautifulSoup
'''
url = 'http://webhelp.esri.com/arcgisserver/9.3/java/geodatabases/definition_frame.htm'
headers = {'User-Agent':"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36"}
data = requests.get(url,headers=headers)
#print(data.text)
'''
soup = BeautifulSoup(open('GIS_dictionary.html','r',encoding='UTF-8'),features="lxml")
#print(soup.prettify())#打印 soup 对象的内容，格式化输出



#关键词：'Letter','GlossaryTerm','Definition'
Letter_list = soup.find_all(attrs={'class':'Letter'})#获取Letter的Tag
GlossaryTerm_list = soup.find_all(attrs={'class':'GlossaryTerm'})#1729个
Definition_list = soup.find_all(attrs={'class':'Definition'})



##定义函数解决None项问题
def dealNone(list_former,list_latter,flag):
    i = 0
    while (i < len(list_former)):
        if (list_former[i].string == None):
            list_latter.append('')
            j = 0
            while j < len(list_former[i].contents):
                #print(type(list_latter[i]))
                #print(type(list_former[i].contents[j].string))
                if (list_former[i].contents[j].string==None):
                    j += 1
                    continue
                #print(i)
                list_latter[i] = list_latter[i] + list_former[i].contents[j].string
                if list_latter[i] == '':
                    list_latter[i] = list_latter[i] + list_former[i].contents[j].string
                j += 1
            #print(flag+' done '+str(i+1)+' !')
        else:
            list_latter.append(list_former[i].string)
            #print(flag+' done '+str(i+1)+' !')
        i += 1
    print('共有 '+flag+' '+str(i)+' 个')

##补充GlossaryTerm中的None项
glo_list = []
dealNone(GlossaryTerm_list,glo_list,'GlossaryTerm')     #1729个词语
##补充Definition中的None项
defi_list = []
dealNone(Definition_list,defi_list,'Definition')#1610个解释；
                                            #set之后差值为378，其中317个空值；
                                            #62个内容重复，故set差值只由空值引起。
                                            #优化：将defList中前后空格和换行格式化去除
                                            #所以只需解决上文代码空值问题，和ol即可。

'''
defi_list = []
i = 0
while (i < len(Definition_list)):
    if (Definition_list[i].string == None):
        defi_list.append('')
        j = 0
        while j < len(Definition_list[i].contents):
            print(type(defi_list[i]))
            print(type(Definition_list[i].contents[j].string))
            print(i)
            defi_list[i] = defi_list[i] + Definition_list[i].contents[j].string
            j += 1
    else:
        defi_list.append(Definition_list[i].string)
    i += 1
    '''
