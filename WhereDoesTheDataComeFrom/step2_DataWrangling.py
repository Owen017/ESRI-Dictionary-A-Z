from bs4 import BeautifulSoup
soup = BeautifulSoup(open('GIS_dictionary.html','r',encoding='UTF-8'),features="lxml")

#tag标签
GlossaryTerm_list = soup.find_all(attrs={'class':'GlossaryTerm'})#完整，1729个
Definition_list = soup.find_all(attrs={'class':'Definition'})#缺<ol>

#添加<ol>标签，将defList补充完整;
#新建字典，通过标签内部的name属性建立key，value的连接;
#初步使用.text方法和.a.attrs['name']方法;
#set数据库时添加首字母字段;
#尝试加入图片
#对所有的See xxxxxx.在前端中添加超链接指向xxxxxx

'''
    完成Definition_list中已有的1610个解释的文本获取和词语对应
'''
defList = []
for i in Definition_list:
    defi = i.text.strip('\n')#修饰definition
    word = i.a.attrs['name'].replace('_',' ')#修饰glossary
    defList.append([defi,word])  #抓取所有解释和词语在小列表，再存入大列表
    if (i.text==''):                #确保没有definition为空
        print(i.a.attrs['name'])
#defList示例[["defi",'word'],["",''],["",''],["",'']...]


'''
    <ol>标签，将defList补充完整，从Ctrl+F得到共有119个<ol>标签
    "1610+119=1729",大成功！1729 == len(GlossaryTerm_list)
'''
#定义函数func_n
#格式化<ol>的definition：首位加"1."；将多个连续的"\n"收为一个；在"\n"后添加"2."等序号
def func_n(txt):
    lstTxt = list(txt)  #因为不能直接修改string，故将其打碎为list进行操作
    n = len(lstTxt)
    newlstTxt = ["1."]  #添加首位的"1."
    count = 2
    for i in range(n-1):
        if lstTxt[i]=='\n' and lstTxt[i]!=lstTxt[i+1] and lstTxt[i+1]!=' ':  #保留单独的"\n"，在其后添加序号；排除'\n'+' '的组合
            newlstTxt.append('\n')
            newlstTxt.append(str(count))
            newlstTxt.append('.')
            count += 1
        if lstTxt[i]!='\n' and lstTxt[i]!=lstTxt[i+1] and lstTxt[i]!='\t':  #放弃连续多个的"\n"、放弃所有的'\t'
            newlstTxt.append(lstTxt[i])
    newlstTxt.append(lstTxt[-1])    #添加for循环里没有的最后一位
    strTxt = ''.join(newlstTxt)     #''.join()函数将list变为string
    return strTxt
#开始实操
ol_list = soup.find_all('ol')
for j in ol_list:
    defi_ol = j.text.strip('\n')
    defi_ol = func_n(defi_ol)
    word_ol = j.a.attrs['name'].replace('_',' ')
    defList.append([defi_ol,word_ol])
