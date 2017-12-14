# Zend解析参数宏

##实例代码

```c
//*实例代码取自ext/standard/string.c trim()
static zend_always_inline void php_do_trim(INTERNAL_FUNCTION_PARAMETERS, int mode){
	zend_string *str;
	zend_string *what = NULL;
	//*Zend解析参数宏====start
	ZEND_PARSE_PARAMETERS_START(1, 2)
		Z_PARAM_STR(str)
		Z_PARAM_OPTIONAL
		Z_PARAM_STR(what)
	ZEND_PARSE_PARAMETERS_END();
	//*Zend解析参数宏====end
	ZVAL_STR(return_value, php_trim_int(str, (what ? ZSTR_VAL(what) : NULL), (what ? ZSTR_LEN(what) : 0), mode));
}
```

##代码解析

```c
//ZEND_PARSE_PARAMETERS_START(1, 2)
//ZEND_PARSE_PARAMETERS_START_EX(flags, min_num_args, max_num_args)
do{
  	const int _flags = (flags);
  	int _min_num_args = (min_num_args);//最小参数数
  	int _max_num_args = (max_num_args);//最大参数数
  	//int _num_args = EX_NUM_ARGS();
  	//#define EX_NUM_ARGS() ZEND_CALL_NUM_ARGS(execute_data)
  	//#define ZEND_CALL_NUM_ARGS(call)
  	int _num_args = (execute_data)->This.u2.num_args;
  	int _i;
  	zval *_real_arg, *_arg = NULL;//真实参数，参数
  	//zend_expected_type _expected_type = IS_UNDEF;
  	zend_expected_type _expected_type = 0;//预期类型
  	char *_error = NULL;//错误
  	zend_bool _dummy;//假设
  	zend_bool _optional = 0;//可选
  	//int error_code = ZPP_ERROR_OK;
  	int error_code = 0;//错误码
  	//*参见问题解析1
  	((void)_i);
  	((void)_real_arg);
  	((void)_arg);
  	((void)_expected_type);
  	((void)_error);
  	((void)_dummy);
  	((void)_optional);
  	do{
      	//if(UNEXPECTED(_num_args < _min_num_args) || (UNEXPECTED(_num_args > _max_num_args) && EXPECTED(_max_num_args >= 0))){
      	//EXPECTED预期中的判定
      	//UNEXPECTED非预期中的判定
      	//这里为代码即注释，通过代码明确表示是否是逾期范围内的判定，无实意
      	if((_num_arg < _min_num_args) || ((_num_args > _max_num_args) && (_max_num_args >= 0))){
          	//if(!(_flags & ZEND_PARSE_PARAMS_QUIET)){
            if(!(_flags & 1<<1)){
            	//zend_wrong_parameters_count_error(_flags & ZEND_PARSE_PARAMS_THROW, _num_args, _min_num_args, _max_num_args);
              	zend_function *active_function = executor_globals.current_execute_data->func;
              	const char *class_name = active_function->common.scope?(active_function->common.scope->name)->val:"";
              	//zend_internal_argument_count_error(throw_ || ZEND_ARG_USES_STRICT_TYPES(), "%s%s%s() expects %s %d parameter%s, %d given", class_name, class_name[0] ? "::" : "", ZSTR_VAL(active_function->common.function_name), min_num_args == max_num_args ? "exactly" : num_args < min_num_args ? "at least" : "at most", num_args < min_num_args ? min_num_args : max_num_args, (num_args < min_num_args ? min_num_args : max_num_args) == 1 ? "" : "s", num_args);
              	va_list va;
              	char *message = NULL;
              	char *format = "%s%s%() expects %s %d parameter%s, %d given";
              	va_start(va, format);
              	//zend_vspprintf(&message, 0, format, va);
              	smart_string buf = {0};//smart_string在Zend/zend_smart_string_public.h
              	if(!message){
                	return 0;
              	}
              	zend_printf_to_smart_string(&buf, format, va);
              	//xbuf_format_converter(void *xbuf, zend_bool is_char, const char *fmt, va_list ap)在main/spprintf.c
              	if(throw_ || ZEND_ARG_USES_STRICT_TYPES()){
                	zend_throw_exception(zend_ce_argument_count_error, message, 0);
                  	//zend_throw_exception(zend_class_entry *exception_ce, const char *message, zend_long code)在Zend/zend_exceptions.c
                }else{
                  	zend_error(E_WARING, "%s", message);
                  	//zend_error(int type, const char *format, ...)在Zend/zend.c
                }
              	efree(message);
              	va_end(va);
          	}
      	}
      	//Z_PARAM_STR(str)
      	//Z_PARAM_STR_EX(str, 0, 0)
      	//Z_PARAM_STR_EX2(str, 0, 0, 0)
      	//Z_PARAM_PROLOGUE(0, 0);
      	++i;
      	ZEND_ASSERT(_i <= _min_num_args || _optional==1);
      	ZEND_ASSERT(_i <= _min_num_args || _optional==0);
      	if(_optional){
        	if(UNEXPECTED(_i > _num_args)) break;
      	}
      	_real arg++;
      	_arg = _real_arg;
      	if(0){
        	ZVAL_DEREF(_arg);
      	}
      	if(0){
        	SEPARATE_ZVAL_NOREF(_arg);	
      	}
      	if(0){
        	SEPARATE_ZVAL_NOREF(_arg);	
      	}
      	if(UNEXPECTED(!zend_parse_arg_str(_arg, &str, 0))){//zend_parse_arg_str(zval *arg, zend_string **dest, int check_null)在zend_API.h
        	_expected_type = Z_EXPECTED_STRING;
          	//error_code = ZPP_ERROR_WRONG_ARG;
          	error_code = 4;
          	break;
      	}
        //Z_PARAM_OPTIONAL
      	_optional = 1;
        //Z_PARAM_STR(what)
      	//Z_PARAM_STR_EX(what, 0, 0)
      	//Z_PARAM_STR_EX2(what, 0, 0, 0)
      	//Z_PARAM_PROLOGUE(0, 0);
      	++i;
      	ZEND_ASSERT(_i <= _min_num_args || _optional==1);
      	ZEND_ASSERT(_i <= _min_num_args || _optional==0);
      	if(_optional){
        	if(UNEXPECTED(_i > _num_args)) break;
      	}
      	_real arg++;
      	_arg = _real_arg;
      	if(0){
        	ZVAL_DEREF(_arg);
      	}
      	if(0){
        	SEPARATE_ZVAL_NOREF(_arg);	
      	}
      	if(UNEXPECTED(!zend_parse_arg_str(_arg, &what, 0))){//zend_parse_arg_str(zval *arg, zend_string **dest, int check_null)在zend_API.h
        	_expected_type = Z_EXPECTED_STRING;
          	//error_code = ZPP_ERROR_WRONG_ARG;
          	error_code = 4;
          	break;
      	}
    //ZEND_PARSE_PARAMETERS_END();
    //ZEND_PARSE_PARAMETERS_END_EX(failure)
  	}while(0);
  	//if(UNEXPECTED(error_code != ZPP_ERROR_OK)){
	if(error_code != 0){
    	//if(!(_flags & ZEND_PARSE_PARAMS_QUIET)){
      	if(!(_flags & 2)){
        	if(error_code == ZPP_ERROR_WRONG_CALLBACK){
          		zend_wrong_callback_error(_flags & ZEND_PARSE_PARAMS_THROW, E_WARING, _i, _error);
        	}else if(error_code==ZPP_ERROR_WRONG_CLASS){
          		zend_wrong_parameter_class_error(_flags & ZEND_PARSE_PARAMS_THROW, _i, _expected_type, _arg);
        	}
      	}
      	return;
    }
}while(0);
```

##问题解析

###1. 变量前加(void)的用法

> 该用法告诉编译器，变量已经使用，不需要进行提示。通常情况下我们在进行编译的时候，根本无法体现出现这个用法的作用，这和编译器参数设定有关。 

**t.c**

```c
//*变量前加(void)demo实例
#include <stdio.h>
int main(void){
  	int i;
  	//(void)i;
  	return 0;
}
```

**gcc**

```shell
#打开gcc所有警告
gcc -Wall t.c -o t.o
#打开gcc所有警告，并将gcc所有的警告当成错误处理
gcc -Wall -Werror t.c -o t.o
```

**echo**

```shell
t.c: In function ‘main’:
t.c:44:6: error: unused variable ‘i’ [-Werror=unused-variable]
  int i;
      ^
cc1: all warnings being treated as errors
```

## 总结

众所周知，在C语言中，宏会在与编译过程中被处理成C的程序，而Zend的参数解析宏通过表面的5个宏命令，内嵌各种宏，从而实现了php的参数解析功能。

